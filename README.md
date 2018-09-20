# Tvi Monitor Bundle #

[![Build Status](https://travis-ci.org/turnaev/TviMonitorBundle.svg?branch=master)](https://travis-ci.org/turnaev/TviMonitorBundle)

[![Build Status](https://scrutinizer-ci.com/g/turnaev/TviMonitorBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/turnaev/TviMonitorBundle/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/turnaev/TviMonitorBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/turnaev/TviMonitorBundle/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/turnaev/TviMonitorBundle/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Code Coverage](https://scrutinizer-ci.com/g/turnaev/TviMonitorBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/turnaev/TviMonitorBundle/?branch=master)


### Checks:

##### php_version

```yaml
tvi_monitor:
  checks:
    php_version:
      check:
        expectedVersion: "7.0"
        operator: ">="
    php_version(s):
      items:
        a:
          check:
            expectedVersion: "7.0"
            operator: ">="
        b:
          check:
            expectedVersion: "7.0"

```
##### php_extension

```yaml
tvi_monitor:
  checks:
    php_version:
      check:
        expectedVersion: "7.0"
        operator: ">="
    php_version(s):
      items:
        a:
          check:
            expectedVersion: "7.0"
            operator: ">="
        b:
          check:
            expectedVersion: "7.0"

```
