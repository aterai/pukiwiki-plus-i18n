#!/bin/sh

cygwin=false;
case "`uname`" in
  CYGWIN*) cygwin=true ;;

esac

JDK_BASE=/usr/lib/jvm
if $cygwin ; then
  JDK_BASE=/cygdrive/c/Program\ Files/Java
fi
JDK_TEMP=$JDK_BASE/jdk$1

if [ "$1" = "" ]; then
  ls --time-style=long-iso -l "$JDK_BASE"
elif [ -e "$JDK_TEMP" ]; then
  export JAVA_HOME=$JDK_BASE/jdk$1
else
  echo Not exist $JDK_TEMP
fi

echo "----"
echo $JAVA_HOME

