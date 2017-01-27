FROM ubuntu:16.04
MAINTAINER DevMob "tech@devmob.com"

ENV PHP_VERSION=7.1
ENV LANG=en_US.UTF-8
ENV TIMEZONE=UTC
ENV DEBIAN_FRONTEND=noninteractive

ARG USER_ID=501

# Configure locale and timezone and install base packages
RUN locale-gen && \
    echo $TIMEZONE > /etc/timezone && \
    ln -fs /usr/share/zoneinfo/$TIMEZONE /etc/localtime && \
    apt-get update && \
    apt-get -yq install --no-install-recommends \
        ca-certificates \
        bzip2 git curl zip unzip acl && \
    apt-get -y clean && \
    rm -rf /var/lib/apt/lists/*

# Install php and base packages
RUN echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu xenial main" >> /etc/apt/sources.list && \
    apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C && \
    apt-get update && \
    apt-get -yq upgrade && \
    apt-get -yq install --no-install-recommends \
        php${PHP_VERSION}-bz2 \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-json \
        php${PHP_VERSION}-sqlite \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-bcmath \
        bzip2 git curl zip unzip acl && \
    apt-get -y clean && \
    rm -rf /var/lib/apt/lists/*

# Configure www-data user
RUN usermod -d /var/www -s /bin/bash -u ${USER_ID} www-data