FROM php:8.0-fpm

RUN apt update -qqy && apt install --no-install-recommends --no-install-suggests -qqy \
    git \
    curl \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    gnupg \
    unzip  \
    nano \ 
    && apt-get clean -qqy && apt-get autoremove --purge -qqy && \
    rm -rf  /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    /usr/share/doc/* /usr/share/groff/* /usr/share/info/* /usr/share/linda/* \
    /usr/share/lintian/* /usr/share/locale/* /usr/share/man/*

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
    gd \
    pdo_mysql \
    mysqli \
    zip \
    intl \
    opcache \
    xml \
    && docker-php-ext-enable \
    intl \
    opcache

# Install composer
COPY --from=composer:2.0.6 /usr/bin/composer /usr/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_12.x  | bash -
RUN apt-get -y install nodejs

RUN useradd docker --shell /bin/bash --create-home \
    && usermod --append --groups sudo docker \
    && echo 'ALL ALL = (ALL) NOPASSWD: ALL' >> /etc/sudoers \
    && echo 'docker:secret' | chpasswd

ARG workdir
ENV PATH="${workdir}/vendor/drush/drush:${PATH}"
COPY . ${workdir}
WORKDIR ${workdir}

RUN chown -R docker:docker ${workdir}
USER docker