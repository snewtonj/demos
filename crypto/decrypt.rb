#!/usr/bin/ruby

require 'base64'
require 'json'
require 'openssl'
require 'webkeychain'

@keyString = nil
@macKey = nil

def pad(str)
  str += '=' while !(str.size % 4).zero?
  str
end

def decode64(str)
  Base64::decode64(pad(str.tr("-_", "+/")))
end

def encryptionKey
  if (@keyString.nil?)
    keys = WebKeychain.loadKeychain
    @keyString = keys['ssocookie']['key']
  end
  @keyString
end

def macKey
  if (@macKey.nil?)
    keys = WebKeychain.loadKeychain
    @macKey = keys['ssocookie']['macKey']
  end
  @macKey
end

def h2b(str)
  stripped = str.gsub(/\s+/,'')
  unless stripped.size % 2 == 0
    raise "Can't translate a string unless it has an even number of digits"
  end
  raise "Can't translate non-hex characters" if stripped =~ /[^0-9A-Fa-f]/
  [stripped].pack('H*')
end

def encrypt(plaintext)
  cipher = OpenSSL::Cipher::AES.new(128, 'CBC')
  cipher.encrypt
  key = h2b(encryptionKey)
  cipher.key = key

  ciphertext = cipher.update(plaintext) + cipher.final
  mac = OpenSSL::Digest::Digest.new("sha256")
  dgst = OpenSSL::HMAC.digest(mac, h2b(macKey), ciphertext)
  dgst+cipher.iv+ciphertext
end

def decrypt(ciphertext)
  # mac digest is the first N bytes where N is the mac size/8 256/8 = 32 in our case
  dgst, msg = ciphertext[0..31], ciphertext[32..-1]
  # iv is the next N bytes
  iv, data = msg[0..15], msg[16..-1]
  
  mac = OpenSSL::Digest::Digest.new("sha256")
  bincalc = OpenSSL::HMAC.digest(mac, h2b(macKey), msg)
  fail "Signature fail - input tampered with?" unless (dgst == bincalc)
#  puts dgst.unpack('h*').first
#  puts bincalc.unpack('h*').first

  decipher = OpenSSL::Cipher::AES.new(128, "CBC")
  decipher.decrypt

  decipher.key = h2b(encryptionKey)
  decipher.iv = iv

  decipher.update(data) + decipher.final
end

ciphertext = ARGV[0]
decoded = decode64(ciphertext)
plainText = decrypt(decoded)

#puts plainText == input

puts plainText

