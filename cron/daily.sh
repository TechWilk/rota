#!/bin/bash

LOG="${BASH_SOURCE%/*}/../logs/daily.log"

date >> $LOG
curl $1/job/daily/$2 2>&1 >> $LOG
echo "-----" >> $LOG
