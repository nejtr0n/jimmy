FROM postgres:10.4
ENV DEBIAN_FRONTEND noninteractive
RUN echo "Europe/Moscow" > /etc/timezone && \
        ln -snf /usr/share/zoneinfo/Europe/Moscow /etc/localtime && \
        dpkg-reconfigure -f noninteractive tzdata && \
        sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
        sed -i -e 's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen && \
        echo 'LANG="ru_RU.UTF-8"'>/etc/default/locale && \
        dpkg-reconfigure --frontend=noninteractive locales && \
        update-locale LANG=ru_RU.UTF-8
RUN apt-get update && apt-get upgrade -y && apt-get install -y --fix-missing postgresql-contrib postgis postgresql-10-postgis-2.4
RUN echo "listen_addresses='0.0.0.0'" >> /usr/share/postgresql/10/postgresql.conf.sample
