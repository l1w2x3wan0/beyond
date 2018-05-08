#### 笔记
1. 重复记录可以插入,重复字段取最后的那个值
2. 命名: ""空字符串/'\0'空字符/'system.'开头/包含'$'
3. insert/insertMany支出插入多行，batchinsert已经过时。
    遇到错误会退出。continueOnError可以跳过错误(shell不支持，客户端支持)。
4. 插入检查: 16M（单文档大小）/文档格式/id/utf-8
    最多消息长度:48M
5. update(filter, "$set":{}, "$inc":{}, "$push":{arr:{}}, )

6. 写入安全机制:客户端设置，应答和非应答（MongoDB\Driver\WriteConcern）
7. 游标,主动销毁/超时销毁
8. 跳过大数据量会慢.