FROM node:24.11.1-alpine3.23

# set working directory
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

ARG HOST_USER
ARG HOST_USER_ID
ARG HOST_GROUP
ARG HOST_GROUP_ID
ARG TIMEZONE

RUN apk add bash

COPY ./develop/docker-entrypoint.sh /usr/local/bin/docker-app-entrypoint
RUN sed -i "s/{USER}/${HOST_USER}/g" /usr/local/bin/docker-app-entrypoint \
    && chmod +x /usr/local/bin/docker-app-entrypoint
RUN chmod +x /usr/local/bin/docker-app-entrypoint

SHELL ["/bin/bash", "-c"]
ENTRYPOINT ["docker-app-entrypoint"]

# see https://stackoverflow.com/questions/49955097/how-do-i-add-a-user-when-im-using-alpine-as-a-base-image
RUN deluser node \
    && addgroup -g ${HOST_GROUP_ID} -S ${HOST_GROUP} \
    && adduser -u ${HOST_USER_ID} -G ${HOST_GROUP} -s /bin/sh -D -g "" ${HOST_USER}

# https://wiki.alpinelinux.org/wiki/Setting_the_timezone
RUN apk --no-cache add tzdata \
    && ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime

RUN chown -R ${HOST_USER}:${HOST_GROUP} /app

USER ${HOST_USER}

RUN wget -qO- https://get.pnpm.io/install.sh | PNPM_VERSION="11.1.1" ENV="$HOME/.bashrc" SHELL="$(which bash)" bash -
RUN source $HOME/.bashrc \
    && pnpm install -g @angular/cli@19 \
    && echo "alias npm='pnpm'" >> $HOME/.bashrc

# start app
CMD pnpm run envs && ng serve --host 0.0.0.0 --poll 2000
