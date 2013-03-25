#!/usr/bin/ruby

require 'yaml'

module WebKeychain

 @keychainDir = '/usr/local/etc'

 def WebKeychain.loadKeychain(dir=@keychainDir)
     begin
       YAML.load(File.open("#{dir}/.webkeychain"))
     rescue ArgumentError => e
       puts "Unable to open keychain: #{e.message}"
     end
  end
end
