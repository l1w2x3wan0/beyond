## 介绍
- ip地址定位
- 参考: http://php.net/manual/zh/book.geoip.php


## 安装
1. 扩展:

地址： http://pecl.php.net/package/geoip

```
yum install GeoIP GeoIP-data GeoIP-devel
wget http://pecl.php.net/get/geoip-1.1.1.tgz
tar zxvf geoip-1.1.1.tgz
cd geoip-1.1.1
/usr/local/php7/bin/phpize
./configure --with-php-config=/usr/local/php7/bin/php-config --with-geoip
make && make install
```
   
   
   
2. ip库：

地址：http://dev.maxmind.com/geoip/legacy/geolite/
```
wget http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz
gzip -d GeoLiteCity.dat.gz
mv GeoLiteCity.dat /var/lib/GeoIP/GeoIPCity.dat
```
   
3. php.ini配置：
```
extension=geoip.so
geoip.custom_directory="/data/service/geoip/"
```

   
4. 重启: 
```
systemctl restart php-fpm
```