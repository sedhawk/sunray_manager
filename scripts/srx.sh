#!/bin/sh

hosts=("tatooine" "dantooine" "corellia" "rishi" "iego")
for host in "${hosts[@]}"; do
  session="`ssh -o BatchMode=yes root@$host \"utdesktop -o \" 2>&1`"
  retcode="$?"
  echo "$host $session"
#  if [ `echo "$session" | wc -l` -gt 1 ]; then
#    echo "Duplicate unit found on $host."
#    echo "$session"
#    exit 9
#  fi
#  if [ "$retcode" -eq 0 ]; then
#    echo "$host $session"
#    exit 0
#  elif [ "$retcode" -eq 255 ]; then
#    echo "Connection to $host failed. Check ssh keys."
#    exit 1
#  fi
done

echo "Unable to find host $id on SunRay Servers."
exit 1


## Generate random utsession -p | grep $id info.
#RANDOM=$id
#user=$RANDOM
#let "user %= 500"
#if [ $user -eq 0 ]; then
#  user="???"
#else
#  user="utku$user"
#fi
#disp=$RANDOM
#let "disp %= 99"
#state=$RANDOM
#let "state %= 5"
#if [ "$state" -lt 2 ]; then
#  state="D"
#elif [ "$state" -eq 3 ]; then
#  state="IU"
#else
#  state="U"
#fi
#host=$RANDOM
#let "host %= 5"
#host="${hosts[$host]}"
#
#ret="pseudo.$id            ???                  $user    $disp   $state"
#
#echo "$host $ret"
#echo "$host" > /tmp/srhost
#exit 0
