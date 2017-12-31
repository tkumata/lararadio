#!/bin/bash

#cmd='mplayer -noconsolecontrols -really-quiet -novideo -af volnorm=2:0.25'
cmd='mplayer -really-quiet -vo null'
url=$1
opt=$2

if [ ! -z "$opt" ]; then
    #nohup $cmd "$url $opt" < /dev/null > /dev/null 2>&1 &
    nohup $cmd "$url" < /dev/null > /dev/null 2>&1 &
else
    nohup $cmd "$url" < /dev/null > /dev/null 2>&1 &
fi

exit 0
