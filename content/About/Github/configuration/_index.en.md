---
title: "Configuration"
date: 2018-12-29T11:02:05+06:00
lastmod: 2020-01-05T10:42:26+06:00
weight: 
draft: false
# search related keywords
keywords: [""]
---

#### Welcome to the OpenACC Validation Suite Configuration File

You can add comments either with the '!' or '#' symbols if you would like. To this end, I will give examples of how to use each of the settings that are configurable in the config file. If you would like one to be active, uncomment it and customize it :)

The first settings are the compiler settings.  Just set this to whatever you want to be invoked as the compiler. If you don't have these set up in your path, you can also give full paths to be used

!CC:gcc
!CPP:g++
!FC:gfortran

In addition to these, you will want to add some flags. Some of the features in the infrastructure use C Pre-Processor directives, so please make sure that is enabled. If enabling them is impossible, the infrastructure will be unable to detect which portions of the tests are causing compilation/runtime errors. 

!CCFlags:-fopenacc -cpp -lm -foffload='-lm'
!CPPFlags:-fopenacc -cpp -lm -foffload='-lm'
!FCFlags:-fopenacc -cpp -lm -foffload='-lm'

Below are a few more things that should probably be specified.  The script will do its best, but sometimes it will need some help.
!TestDir:/home/<user>/OpenACCV-V/Tests
!BuildDir/home/<user>/OpenACCV-V/Build

By default, the script will show very few messages about what it is doing.  Uncomment the entries below if you would like to be given more information during runtime about the following:

!ShowCommands
!ShowResults
!ShowErrors
!ShowOutput
!ShowOSErrors
!ShowInfo
!ShowAll

The infrastructure can also be used to extend the existing testsuite through the use of "Mutators" which will be called at runtime with a test object.  More information can be found in the demo mutator file.  These can be added with the command below along with the path of the mutator.

!Mutator:/home/OpenACCTestsuite/demo_mutator.py

The infrastructure also allows for user to specify subsets of the testsuite to be run. If these are ommited, the full testsuite will be run.

!IncludeTags:parallel,kernels
!ExculdeTags:async
!IncludeTests:parallel_loop_async.c
!ExcludeTests:acc_copyin.c

Each of these is or-inclusive.  If something has one or more of the Include tags and does not have one or more of the exclude tags, it will be included.  If you want something more complex, you can use the following configuration:

!TagEvaluationString:attach&(devonly|!reference-counting)

This is a demonstration that uses all the valid characters. (, ), &, |, !

While additional tags may be added in the future, at the time of writing, we have the following tags:

!async
!atomic
!attach
!auto
!combined-constructs
!compatibility-features
!construct-independent
!data
!data-region
!declare
!default
!default-mapping
!devonly
!executable-data
!firstprivate
!host-data
!if
!init
!internal-control-values
!kernels
!loop
!nonvalidating
!parallel
!present
!private
!reduction
!reference-counting
!routine
!runtime
!serial
!set
!shutdown
!structured-data
!syntactic
!tile
!update
!vector
!wait

In addition, you can specify the OpenACC version that the compiler implements with the following lines: (The last one can be used as a shorthand for all three being set to the same version)

!C_ACC_Version:2.5
!CPP_ACC_Version:2.7
!Fortran_ACC_Version:3.0
!ACC_Version:1.0

If these are ommited, the infrastructure will try to detect the version of OpenACC being implemented

If you would like to set a seed for the random number generator, you can pass one in with the following:

!Seed:46296542

If you would like to pass in an environment file that would be generated with "python3 infrastructure.py -env=<output>
you can use the environment file with the following:

!Env:<output>

If you would like to specify a timeout on the shell operations (both compilation and runtime) you can do so with the following (specified in seconds):

!Timeout:10

If you would like to run the tests without trying to detect causes of runtime errors and  compilation errors you can use the following:

!Fast:True

To specify the output format, you can use the following with the following values (json, html, txt):

!ResultsFormat:json

If you specify the html output format, the file that is output will still be in json format.  The output file should afterward be placed into the directory results_template and renamed to results.json.  The results.html can then be loaded in a browser.  

To specify that you would like to allow for results to loaded from previous runs (when their configurations and systems match):

!AllowPartial:True

If you would like a command to be executed at the beginning of the testsuite run:

!Once:echo "Hello World"

Additionally, you can also specify commands to be run before compilation, after compilation, before runtime, and after runtime with the following:

!PreCompileCommands:
!PostCompileCommands:
!PreRunCommands:
!PostRunCommands:

Each of these can be used multiple times to add additional commands to be run. Also, each can be passed variables from the infrastructure which are each prefixed with "$"

!$CC
!$CPP
!$FC
!$COMPILER
!$FLAGS
!$TEST_DIR
!$BUILD_DIR
!$EXECUTABLE_PATH
!$SOURCE_PATH
!$TEST_NAME
!$EXCLUDED_TESTS
!$INCLUDED_TESTS
!$COMPILATION_COMMAND (valid only on PreCompileCommands and PostCompileCommands)
!$RETURNCODE (valid only on PostCompileCommands and PostRunCommands)
!$OUTPUT (valid only on PostCompileCommands and PostRunCommands)
!$ERRORS (valid only on PostCompileCommands and PostRunCommands)
!$RUN_ATTEMPT (valid only on PreRunCommands and PostRunCommands)

Also, if you would like to specify a prefix on the executable, you can use:

!RuntimePrefix:<prefix>

This can be used to test host-fallback on gcc for example with RuntimePrefix:ACC_DEVICE_TYPE=None

