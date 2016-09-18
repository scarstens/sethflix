
exec { 'apt-get update':
  path => '/usr/bin',
}

class { '::mysql::server':
  root_password => 'bn67hgyt',
}

mysql::db { 'fansided_api':
  user     => 'fansided_admin',
  password => 'bn67hgyt',
  host     => 'localhost',
  sql      => '/var/www/api.fansided.com/htdocs/server/puppet/modules/mysql_server/files/fansided_api_schema.sql',
}

package { 'nginx':
  ensure => 'present',
  require => Exec['apt-get update'],
}

service { 'nginx':
  ensure => running,
  require => Package['nginx'],
}

# Add vhost template
file { 'vagrant-nginx':
  path => '/etc/nginx/sites-available/api.fansided.dev',
  ensure => file,
  require => Package['nginx'],
  source => 'puppet:///modules/nginx/api.fansided.dev',
}

# Disable default nginx vhost
file { 'default-nginx-disable':
  path => '/etc/nginx/sites-enabled/default',
  ensure => absent,
  require => Package['nginx'],
}

# Symlink our vhost in sites-enabled
file { 'vagrant-nginx-enable':
    path => '/etc/nginx/sites-enabled/api.fansided.dev',
    target => '/etc/nginx/sites-available/api.fansided.dev',
    ensure => link,
    notify => Service['nginx'],
    require => [
        File['vagrant-nginx'],
        File['default-nginx-disable'],
    ],
}
