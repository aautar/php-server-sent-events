$startScript = <<START_SCRIPT
sudo service apache2 restart
START_SCRIPT

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/mantic64"
  config.vm.provision :shell, path: "localdev/bootstrap.sh"
  config.vm.provision "shell", inline: $startScript, run: "always"
  config.vm.network "private_network", type: "dhcp"
end
