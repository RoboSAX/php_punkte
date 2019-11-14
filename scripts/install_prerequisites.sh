#!/bin/bash

#***************************[system]******************************************
# 2019 11 14

sudo apt-get update
if [ $? -ne 0 ]; then return -1; exit -1; fi


#***************************[tools]*******************************************
# 2019 11 14

sudo apt-get install -y make


#***************************[documentation]***********************************
# 2019 11 14

sudo apt-get install -y doxygen


#***************************[done]********************************************
# 2019 11 14

if [ $? -ne 0 ]; then
    echo "There have been errors :-("
    return -1
    exit -1
else
    echo "all done :-)"
fi
