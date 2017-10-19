# easykin
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square "Software License")](LICENSE)
[![Build Status](https://travis-ci.org/easyops-cn/easykin.svg?branch=master)](https://travis-ci.org/easyops-cn/easykin)

这是一个简单的Zipkin PHP库，根据 [官方概念文档](http://zipkin.io/pages/instrumenting.html) 实现，主要用于PHP实现的web服务链路追踪，并针对B3 Propagation封装了专用的Trace类，方便Http服务的接入。

## Quick Start

#### 初始化

定义服务基础信息:

```php
EasyKin::setEndpoint(
	'My service', // 服务名
	'127.0.0.1', // 服务IP
	80); // 服务端口
```

定义trace信息上报方式，提供FileLogger和HttpLogger两种方式，可以基于Logger接口实现更多上报方式：

```php
EasyKin::setLogger(
	new easyops\easykin\logger\HttpLogger(
		'http://127.0.0.1:9411/api/v1/spans', false));
```

***初始化Trace***

对于前端（链路的源头）：

```php
EasyKin::setTrace(new \easyops\easykin\core\Trace('get:/login'));
```

对于后端，需要提取请求Header中的B3信息:

```php
$traceId = !empty($_SERVER['HTTP_X_B3_TRACEID']) ? $_SERVER['HTTP_X_B3_TRACEID']) : null;
$parentSpanId = !empty($_SERVER['HTTP_X_B3_PARENTSPANID']) ? $_SERVER['HTTP_X_B3_PARENTSPANID'] : null;
$spanId = !empty($_SERVER['HTTP_X_B3_SPANID']) ? $_SERVER['HTTP_X_B3_SPANID'] : null;
$isSampled = !empty($_SERVER['HTTP_X_B3_SAMPLED'])) ? $_SERVER['HTTP_X_B3_SAMPLED'] : null;

EasyKin::setTrace(new \easyops\easykin\core\Trace('get:/login', $sampled, $traceId, $parentSpanId, $spanId));
```

也可以使用封装好的HttpTrace类，前后端调用方式都一致：

```php
EasyKin::setTrace(new \easyops\easykin\core\HttpTrace());
```

以上动作均需在你的服务程序入口处尽早完成，在程序结尾处执行上报方法：

```php
EasyKin::trace();
```

***注意：如果服务程序因为异常而中断，EasyKin依然会上报trace信息***

#### 新建一个Span

当你的服务发起请求时，需要新建一个Span来承载该请求的信息：

```php
$span = EasyKin::newSpan(
	'get:/users', // span名字，这里以请求url作为名字
	'users service',  // 请求的服务名
	'127.0.0.1',      // 请求的服务IP
	8080);            // 请求的服务端口
```

然后执行你的请求逻辑。当请求结果返回后，应尽快执行：

```php
$span->receive();
```

以便准确记录该请求的结束时间。

#### 执行上报

当服务应用程序处理完一次请求后，需要执行上报动作：

```php
EasyKin::trace();
```