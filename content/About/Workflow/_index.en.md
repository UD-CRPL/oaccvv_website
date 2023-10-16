---
title: "Workflow"
lastmod: 2022-01-25T10:42:26+06:00
weight: 3
draft: false
# search related keywords
keywords: ["Workflow of the project"]
---

Although writing tests in a testsuite is important, it is not the only factor that guarantees a successful testsuite. It is necessary to find a workflow and an infrastructure that validates conformance. These should also open up the possibilities of discussion between different members of the project and the community, in order to reduce the chance of making mistakes while creating the tests.

One of the most challenging questions that validation and verification faces is: *How can we guarantee that the test we are using is valid?*. Although this non-trivial question does not have a single correct answer, we believe that the workflow we have been developing throughout our work is a good approach to guarantee that the tests we write and propose are as correct as possible.

Our current workflow is displayed in the following figure:
![image example](VnV_Workflow.png "image")

## Workflow explanation

Our tests cover important features of OpenACC that we believe are testable. OpenACC has many features, from directives and clauses to the runtime directives and its external library functions, which are constantly being updated. For each feature, we start by studying it and discussing it. We usually discuss with compiler developers, involved parties, and all the members of our team to guarantee that our understanding of the particular feature is correct. After mapping out our understanding, a test is formulated, initially to test our logic without implementing the feature, then implementing the feature. This test goes through a development phase of validation with various compilers. If the tests are not successful, concerns are raised and steps are taken to determine where the mistake lies: in the compiler, the logic, or the test code. The test is reviewed by multiple programmers for correctness and at this time any comments are made. Once a test is deemed valid, it is added to the list of tests in our testsuite.

Each tests can either *PASS* or *FAIL*. If it passes, no further action is performed during development, the test is then reviewed and added to our testsuite. But if it fails, it is open to our community one more time for a final discussion. If this discussion leads to the test being correct, we add it to the master branch (current release branch) of our repository to make it available to our final V&V testing suite. If it determined to be wrong, the comments and reason are gather and the tests goes back to development and the cycle starts again.

Sometimes testing of features is not possible given the available API and directives available to us. In such cases the logic of the test will determine a pass or fail, until the feature is available.
