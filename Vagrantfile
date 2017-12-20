# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "ubuntu/xenial64"
    
    config.vm.network 'private_network', ip: '192.168.33.11'

    config.vm.provision :shell, path: "build/install/vagrant/provision.sh", args: "/vagrant rota.example.com admin@example.com"

    config.vm.provider "virtualbox" do |vb|
        # Display the VirtualBox GUI when booting the machine
        vb.gui = false
        # Customize the amount of memory on the VM:
        vb.memory = 2048
        # set number of cpus to use:
        vb.cpus = 2
    end
end