---
title: "About"
lastmod: 2022-01-25T10:42:26+06:00
draft: false
icon: "ti-panel"
# search related keywords
keywords: ["about", "who"]
type : about
description: "About the Project"
id: "about"
---


Complexity in computer systems architectures and programming environments has been increasing during the last decade. Not only the number of cores has considerably increased, but also the adoption of heterogenous computation. Heterogenous computation uses acceleration devices to speed up segments of computation that fit their capabilitites. In spite of the clear performance advantages that heteorgenous computation brings, it requires additional orchestration between the different hardware architectures and resources, which results in a considerable burden for the programer. As a result, directive based programming models and frameworks have been created and adapted to support heterogenous computation. In particular, OpenACC, as one of the newer parallel programming frameworks, released its first verison in 2011 [version 1.0](https://www.openacc.org/sites/default/files/inline-files/OpenACC_1_0_specification.pdf). 

However, it is expected that an increased complexity in hardware and programming models introduces new challenges to software testing and reliability. Fortunately for the case of programming models, programming languages and programming frameworks, it is common to find specifications that work as a set of "rules to follow" for implementation developers. These rules are not only used by users to easily move from implementation to implementation, but they are also a great starting point for **testing, verification and validation** of compliance with such specifications. In the case of OpenACC, there is a current need of a common testing framework that allows compiler implementations and users to meassure their level of compliance with the specifications.

Supported by OpenACC, this project entails functional test cases (unit test cases and micro applications) that helps assess compiler implementations and how compliant they are with the specification. This effort helps in developing a high quality compiler. In this website we populate results of OpenACC compiler implementations targeting different platforms.

#### Having issues?

Please use our [Github issue tracker](https://github.com/OpenACCUserGroup/OpenACCV-V/issues) to report any issues that you are having with out test suite

##### Found a bug or have a comment?

Please use the issue tracker above or any bugs or comments. **Please make sure that you are logged into github with your username**. We encourage people's participation. The success of this test suite comes from the effort of the OpenACC community.

#### Note

Thanks for contributing to our project. By contributing to our project you agree to our [license agreement](/license) and allow us to use and distribute your code.
