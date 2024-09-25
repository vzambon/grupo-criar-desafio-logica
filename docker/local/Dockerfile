FROM phpswoole/swoole:php8.3-alpine

ARG USER_ID
ARG GROUP_ID

ENV RUNNING_IN_DOCKER=true

RUN apk upgrade && apk update \
    && apk add --no-cache sudo \
    npm \
    nodejs \
    supervisor \
    unzip \
    zlib-dev \
    libpng-dev \
    autoconf \
    gcc \
    g++ \
    make \
    wget \
    libaio \
    libnsl \
    libc6-compat \
    libzip-dev \
    libxrender \ 
    fontconfig \
    freetype \
    libxext \
    libx11 \
    supercronic \
    git \
    rsync \
    openssh-client \
    zsh \
    nano \
    mysql-client \
    #solve: mysqldump: Plugin caching_sha2_password could not be loaded
    mariadb-connector-c

# Install PHP extensions and enable them
RUN docker-php-ext-install gd zip pcntl

# Create a user/group with UID 1000 GID 1000
RUN addgroup -g ${GROUP_ID} laravel \
    && adduser -u ${USER_ID} -G laravel -s /bin/sh -D laravel \
    && echo 'laravel:password1234' | chpasswd

# Add laravel user to sudoers with NOPASSWD option
RUN echo 'laravel ALL=(ALL) NOPASSWD:ALL' > /etc/sudoers.d/laravel \
    && chmod 0440 /etc/sudoers.d/laravel

# Create cron configuration for Laravel schedules 
RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1" > /etc/crontabs/laravel-cron.txt

COPY ./supervisor.conf /etc/supervisor.conf

COPY ./laravel-install.sh /usr/local/bin/laravel-install.sh
RUN chmod +x /usr/local/bin/laravel-install.sh

EXPOSE 9051

USER laravel

RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" --unattended || true

WORKDIR /var/www

ENTRYPOINT [ "/usr/local/bin/laravel-install.sh" ]

# Start Supervisor when the container starts
CMD ["supervisord", "-c", "/etc/supervisor.conf"]
