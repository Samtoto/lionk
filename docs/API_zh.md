## Blogs

与 blogs 有关的 API

### 获取 blogs 列表

#### 说明：获取 blogs 列表的 json 数据

http请求方式： GET  
URI：`blog/all`  
Controller: `App\Http\Controllers\BlogController@all`  
Auth: No

#### 参数：无

#### 返回说明  

```json

[
    {
        "id": 13,
        "title": "测试Community with update",
        "content": "测试Community 添加 blog with new api update 2",
        "deleted_at": null,
        "created_at": "2020-07-19T13:34:22.000000Z",
        "updated_at": "2020-08-10T07:31:15.000000Z",
        "user_id": 1,
        "community_id": 52,
        "img_path": null,
        "comment_count": 28,
        "markdown_content": "<p>测试Community 添加 blog with new api update 2<\/p>
",
        "user": {
            "id": 1,
            "name": "Sam",
            "email": "sam@lionk.test",
            "email_verified_at": "2020-07-13T17:31:29.000000Z",
            "created_at": "2020-07-12T17:31:29.000000Z",
            "updated_at": "2020-07-12T17:31:29.000000Z"
        },
        "community": {
            "id": 52,
            "name": "Futurology",
            "deleted_at": null,
            "created_at": "2020-07-17T14:24:59.000000Z",
            "updated_at": "2020-07-17T14:24:59.000000Z",
            "user": [
                {
                    "id": 1,
                    "name": "Sam",
                    "email": "sam@lionk.test",
                    "email_verified_at": "2020-07-13T17:31:29.000000Z",
                    "created_at": "2020-07-12T17:31:29.000000Z",
                    "updated_at": "2020-07-12T17:31:29.000000Z",
                    "pivot": {
                        "community_id": 52,
                        "user_id": 1
                    }
                }
            ]
        }
    },
    {
        "id": 14,
        "title": "测试数据1",
        "content": "测试数据1",
        "deleted_at": null,
        "created_at": "2020-07-19T13:40:46.000000Z",
        "updated_at": "2020-08-20T09:10:22.000000Z",
        "user_id": 1,
        "community_id": 58,
        "img_path": null,
        "comment_count": 11,
        "markdown_content": "<p>测试数据1<\/p>
",
        "user": {
            "id": 1,
            "name": "Sam",
            "email": "sam@lionk.test",
            "email_verified_at": "2020-07-13T17:31:29.000000Z",
            "created_at": "2020-07-12T17:31:29.000000Z",
            "updated_at": "2020-07-12T17:31:29.000000Z"
        },
        "community": {
            "id": 58,
            "name": "ToiletPaperUSA",
            "deleted_at": null,
            "created_at": "2020-07-17T14:24:59.000000Z",
            "updated_at": "2020-07-17T14:24:59.000000Z",
            "user": [
                {
                    "id": 1,
                    "name": "Sam",
                    "email": "sam@lionk.test",
                    "email_verified_at": "2020-07-13T17:31:29.000000Z",
                    "created_at": "2020-07-12T17:31:29.000000Z",
                    "updated_at": "2020-07-12T17:31:29.000000Z",
                    "pivot": {
                        "community_id": 58,
                        "user_id": 1
                    }
                }
            ]
        }
    }
]

```

#### 返回参数说明

参数|描述
|:---|:---|
id | blog 的 id
title | blog 的标题
content | blog 的内容
deleted_at | 删除时间
created_at | 创建时间
updated_at | 更新时间
user_id | 该 blog 所属用户的id
community_id | blog 所属板块的id
img_path | blog 的 图片路径
comment_count | 该 blog 有多少留言
markdown_content | blog 内容的 markdown 格式
---|---
user.id | 该 blog 所属用户的 id
user.name | 该 blog 所属用户的名字
user.email | 该 blog 所属用户的 email
user.email_verified_at | 该 blog 所属用户的邮件确认时间
---|---
community.id | 该 blog 所属板块
community.name | 该 blog 所属板块的名字
community.deleted_at | 该 blog 所属板块的删除时间
community.user | 该用户是否加入了该板块，如果无则返回 []
---|---
community.user.id | 类似于 user.id

### 创建一个纯文本的 blog

#### 说明：创建一个 blog

http请求方式： POST  
URI：`blog/add`  
Controller: `App\Http\Controllers\BlogController@add`  
Auth: Yes

#### 调用示例

```json
{
    "title": <TITLE>,
    "community": <COMMUNITY_ID>,
    "content": <CONTENT>,
}

```

#### 参数

参数|是否必须|说明
---|---|---
title|是|blog 的标题
community|是| blog 所属板块
content| 否|blog 的内容

#### 返回说明

TODO

#### 错误情况返回

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "The title field is required."
        ],
        "community": [
            "The community field is required."
        ]
    }
}
```

### 创建一个图片类型的 blog

#### 说明：创建一个 blog

http请求方式： POST  
URI：`blog/addImg`  
Controller: `App\Http\Controllers\BlogController@addImg`  
Auth: Yes

#### 调用示例

```json
{
    "title": <TITLE>,
    "community": <COMMUNITY_ID>,
    "image": <IMAGE>,
}

```

#### 参数

参数|是否必须|说明
---|---|---
title|是|blog 的标题
community|是| blog 所属板块
image| 是|blog 的图片，只能是图片

#### 返回说明

TODO

#### 错误情况返回

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": [
            "The title field is required."
        ],
        "community": [
            "The community field is required."
        ]
    }
}
```

### 更新 blog

#### 说明：更新 blog，已删除的不能更新

http请求方式： PUT  
URI：`blog/{blog_id}`  
Controller: `App\Http\Controllers\BlogController@update`  
Auth: Yes

### 删除 blog

#### 说明：删除 blog，已删除不能再次删除

http请求方式： DELETE  
URI：`blog/{blog_id}`  
Controller: `App\Http\Controllers\BlogController@delete`  
Auth: Yes

### 编辑 blog

#### 说明：编辑 blog，已删除不能编辑

http请求方式： PUT  
URI：`blog/{blog_id}/edit`  
Controller: `App\Http\Controllers\BlogController@edit`  
Auth: Yes

## Comments

### 获取 comments 列表

#### 根据 blog_id 返回所属的 comments

http请求方式： GET  
URI：`blog/{blog}/comment`  
Controller: `App\Http\Controllers\CommentController@index`  
Auth: No

### 添加一个 comment

#### 添加 comment

http请求方式： POST  
URI：`comment/add_sub`  
Controller: `App\Http\Controllers\CommentController@addSub`  
Auth: Yes

### 更新一个 comment

#### 更新 comment

http请求方式： PUT  
URI：`comment/{comment_id}`  
Controller: `App\Http\Controllers\CommentController@update`  
Auth: Yes

### 删除一个 comment

#### 删除 comment

http请求方式： DELETE  
URI：`comment/{comment_id}`  
Controller: `App\Http\Controllers\CommentController@delete`  
Auth: Yes

## Communities

### 获取板块列表

#### 获取 community

http请求方式： GET  
URI：`community/all`  
Controller: `App\Http\Controllers\CommunityController@all`  
Auth: No

### 变更用户是否加入板块

#### 变更板块加入状态

http请求方式： GET  
URI：`community/change_status/{community_id}`  
Controller: `App\Http\Controllers\CommunityController@changeStatus`  
Auth: Yes
