@echo off

set JDK_BASE=C:\Program Files\Java\

if "%~1" == "" goto list

set JDK_TEMP=%JDK_BASE%jdk%~1

if not exist "%JDK_TEMP%" goto error
set JAVA_HOME=%JDK_TEMP%
goto end

:list
dir "%JDK_BASE%jdk*"
goto end

:error
echo ----
echo Not exist %JDK_TEMP%

:end
echo ----
echo JAVA_HOME=%JAVA_HOME%
"%JAVA_HOME%\bin\java" -version
set JDK_BASE=
set JDK_TEMP=
