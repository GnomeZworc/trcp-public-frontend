

NAME=${1}
docker kill nginx php > /dev/null 2> /dev/null
docker rm nginx php > /dev/null 2> /dev/null
docker network rm ${NAME} > /dev/null 2> /dev/null
