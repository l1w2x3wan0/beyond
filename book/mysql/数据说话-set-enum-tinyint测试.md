---
title: 数据说话
tags: set/enum/tiny测试
---

### 表结构
 ``` sql
CREATE TABLE `test_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `myset` set('black','lock','denied','normal','vip') CHARACTER SET utf8mb4 DEFAULT 'black',
  `myenum` enum('black','lock','denied','normal','vip') CHARACTER SET utf8mb4 DEFAULT 'black',
  `mytiny` tinyint(3) unsigned DEFAULT '1' COMMENT '1=''black'',2=''lock'',3=''denied'',4=''normal'',5=''vip''',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='测试表：测试tinyint/enum/set性能';
```
### 测试环境
- win10  i7 6500u 2.5GHz  8G RAM 
- mysql5.7.14
- 数据量 1000万
- 测试软件： navicat
- mysqlslap

### 测试结果

|  操作类型 |    sql                                                  |        时间          | mysqlslap10客户端 |
|:-----| :-------------------------------------------------------------------------|:------ | :----------------------|
| SELECT * FROM test_set WHERE FIND_IN_SET('black', myset) |FIND_IN_SET | 2.220s | 14.031 |
| SELECT * FROM test_set WHERE mytiny = 1 |tiny |  2.249s |  14.515 | 
| SELECT * FROM test_set WHERE myenum = 'black' |enum |  2.412s | 18 |
| SELECT * FROM test_set WHERE myset = 'black' |set |  3.855s | 28.406 | 
| ELECT count(*) FROM test_set WHERE FIND_IN_SET('black', myset) |FIND_IN_SET |  0.477s| 4.046 | 
| SELECT count(*) FROM test_set WHERE mytiny =1 |tiny |  0.583s | 4.156 | 
| SELECT count(*) FROM test_set WHERE myenum = 'black'  |enum |  0.798s | 6.688 | 
| SELECT count(*) FROM test_set WHERE myset = 'black'  |set |  2.055s | 18.437 |


### 结论
- set 类型使用FIND_IN_SET后，速度比想象中快（意料之外）
- enum 表现如预期，性能稍差
- tinyint 排第2
- 用mysqlslap模拟10客户端压测，结果大致相同

### todo
调用存储过程来测试