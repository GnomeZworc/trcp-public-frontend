

NAME=${1}
docker kill nginx php postgres > /dev/null 2> /dev/null
docker rm nginx php postgres > /dev/null 2> /dev/null
docker network rm ${NAME} > /dev/null 2> /dev/null
