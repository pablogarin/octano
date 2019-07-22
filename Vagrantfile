# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.configure("2") do |config|
  config.vm.box = "debian/stretch64"
  config.vm.hostname = "Octano"
  config.vm.network "private_network", ip: "192.168.10.10"
  config.vm.synced_folder ".", "/vagrant", type: "rsync",
    rsync__exclude: [".git/", "node_modules/", "package-lock.json", "public/css/app.css", "public/js/app.js", "public/mix-manifest.json"]
  config.vm.provision :shell, path: "script.sh"
end
