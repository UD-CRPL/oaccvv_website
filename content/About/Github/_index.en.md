---
title: "Getting Started"
lastmod: 2022-01-25T10:42:26+06:00
draft: false
icon: "ti-package"
# search related keywords
keywords: ["Install", "GitHub"]
type: docs
description: "Get started with using our testsuite"
---
Before using our testsuite, please make sure that you have one or more compilers that support OpenACC. Current support status can be found on [Resources](/resources/)

## Getting our testsuite

Once you have a working compiler, you can access our **[repository](https://github.com/OpenACCUserGroup/OpenACCV-V.git)** and obtain all the tests that are currently available:

```bash
git clone https://github.com/OpenACCUserGroup/OpenACCV-V.git
```

We have included a README and config file with the instructions to compile and execute the tests. The config (init_config) contains multiple options, and it can be expanded to support other systems. Following are some examples to run the tests

```bash
# Example
nvc -fast -acc=gpu <testfilename>.c; # using NVHPC SDK, compile and run a C test

python3 infrastructure.py -c=init_config.txt -o=outputfile.json # Compile all the tests with the infrastructure
```

If everything goes well there should be a similar output to this:

```

Version detection determined compiler OpenACC Version for C as: 2.6
Could not detect OpenACC version for CPP.  Version testing file failed compilation.  Setting to default(2.7)
Version detection determined compiler OpenACC Version for Fortran as: 2.6

Evaluating test acc_async_test.c.  Will it run: True
Evaluating test acc_async_test_all.c.  Will it run: True
Evaluating test acc_copyin.c.  Will it run: True

.....

Time to complete: 1661.265315601002
Time spent running/compiling: 1469.5791327649822
```

The header always includes the version of the compilers that are being used. If the execution is successful the lines in the middle will display true, like "Evaluating test acc_async_test.c.  Will it run: True". If false something is wrong with the compiler or the setup. For the entire run one output file will be created to the name that was specified or the default name "output_file". Read more about this on the [Documentation section](/documentation/)

