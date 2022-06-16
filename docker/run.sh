

echo -e "\n## pull nginx"
docker pull nginx:1.20.2-alpine

echo -e "\n## pull php"
docker build docker -t php

echo
NAME=${1}
DIR=${2}
DOCKER="${DIR}/docker"
SOURCES="${DIR}/sources"
CONFIG="${DOCKER}/config"

docker kill nginx php > /dev/null 2> /dev/null
docker rm nginx php > /dev/null 2> /dev/null
docker network rm ${NAME} > /dev/null 2> /dev/null

docker network create ${NAME} > /dev/null 2> /dev/null


docker run -d --name php \
  --net ${NAME} \
  -v "${SOURCES}:/script" \
  php > ${DOCKER}/log/php.log 2>${DOCKER}/log/php.log \
  && echo "Docker php is running" \
  || echo "Docker php had an error"

docker run -d --name nginx \
  --net ${NAME} \
  -v "${SOURCES}:/usr/share/nginx/html:ro" \
  -v "${DOCKER}/log:/var/log/nginx" \
  -v "${CONFIG}/nginx.conf:/etc/nginx/nginx.conf:ro" \
  -p "0.0.0.0:80:80" \
  nginx:1.20.2-alpine > ${DOCKER}/log/nginx.log 2>${DOCKER}/log/nginx.log \
  && echo "Docker nginx is running" \
  || echo "Docker nginx had an error"
