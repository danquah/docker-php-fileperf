#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

run ()
{ 
  TIMES=$1
  COUNT=$2
  SIZE=$3
  
  if [ $4 -eq 0 ] 
  then
  	# use volume
  	echo
    echo "Performing ${TIMES} volume-based tests with ${COUNT} files of ${SIZE}KB"
    LOCAL=0
  else
    # container-local
    echo
    echo "Performing ${TIMES} container-local tests with ${COUNT} files of ${SIZE}KB"
    LOCAL=1
  fi

  for i in $(seq $TIMES); do
  	echo "Run $i"
  	 if [ $LOCAL -eq 0 ] 
  	 then
  	   docker run --rm -ti -v $HOME/storagetest:/storage danquah/php-fileperf $COUNT $SIZE
  	 else
	   docker run --rm -ti danquah/php-fileperf $COUNT $SIZE
  	 fi
  done
}

echo
echo "Local"
run 3 10000 1 1
run 3 1000 10 1
run 3 100 100 1

echo
echo "Volume-based"
run 3 10000 1 0
run 3 1000 10 0
run 3 100 100 0
