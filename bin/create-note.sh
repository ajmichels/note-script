#!/bin/sh

NOTE_HOME=${NOTE_HOME-$HOME/notes}

if [ ! -d $NOTE_HOME ]; then
    mkdir $NOTE_HOME
fi

# Define the filename
CURRENT_DATE=$(date "+%Y-%m-%d-%H-%M-%S")
CURRENT_DATE_STRING=$(date "+%Y-%m-%d %H:%M:%S")
FILENAME=$CURRENT_DATE.md
FILEPATH=$NOTE_HOME/$FILENAME

# Touch the file (create it if it doesn't exist)
if [ ! -f $FILEPATH ]; then
    printf "# Note ${CURRENT_DATE_STRING}\n\n" > $FILEPATH
fi

# Output the path
echo $FILEPATH
