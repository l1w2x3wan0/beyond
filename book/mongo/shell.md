
#### 删除重复记录
```shell
db.getCollection('orders_135').aggregate([
        { $group: { 
            _id: { user_id: "$user_id", order_type: "$order_type", order_number: "$order_number" }, 
            uniqueIds: { $addToSet: "$_id" },
            count: { $sum: 1 } 
        }}, 
        { $match: { 
            count: { $gt: 1 } 
        }}
    ]).forEach(function(doc){
        doc.uniqueIds.shift();
        db.orders_135.remove({_id: {$in: doc.uniqueIds}});
    })
```

#### 建立索引
```shell
db.getCollection('orders_135').ensureIndex({
    order_number:1,
    type_id:1,
    user_id:1
    },
    {unique: true}
    )
```