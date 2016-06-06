# Import image from bitnami
FROM bitnami/php-fpm

# Install PHP debug tools
RUN apt-get update && \
    apt-get install -y autoconf build-essential && \
    pecl install xdebug && \

    # Config XDebug
    echo "[xdebug]" >> /bitnami/php-fpm/conf/php.ini && \
    echo "zend_extension=/opt/bitnami/php/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so" >> /bitnami/php-fpm/conf/php.ini && \
    echo "xdebug.remote_enable=on" >> /bitnami/php-fpm/conf/php.ini && \
    echo "xdebug.remote_autostart=off" >> /bitnami/php-fpm/conf/php.ini
