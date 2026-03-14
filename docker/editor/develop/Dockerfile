FROM node:24.11.1-alpine3.23

# set working directory
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

ARG HOST_USER
ARG HOST_USER_ID
ARG HOST_GROUP
ARG HOST_GROUP_ID

COPY ./develop/docker-entrypoint.sh /usr/local/bin/docker-app-entrypoint
RUN chmod +x /usr/local/bin/docker-app-entrypoint
ENTRYPOINT ["docker-app-entrypoint"]

# see https://stackoverflow.com/questions/49955097/how-do-i-add-a-user-when-im-using-alpine-as-a-base-image
RUN deluser node \
    && addgroup -g ${HOST_GROUP_ID} -S ${HOST_GROUP} \
    && adduser -u ${HOST_USER_ID} -G ${HOST_GROUP} -s /bin/sh -D -g "" ${HOST_USER}

RUN npm install -g pnpm \
    && npm add -g @angular/cli@19

RUN chown -R ${HOST_USER}:${HOST_GROUP} /app

USER ${HOST_USER}

# start app
CMD pnpm run envs && ng serve --host 0.0.0.0 --poll 2000
