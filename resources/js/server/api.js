// import axios from 'axios' // imported in ../bootstrap.js

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    if (response.status === 200) {
        // console.log('success')
        return Promise.resolve(response);
    } else {
        return Promise.reject(response)
    }
}, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
});

/* blog */

export const getAllBlogs = data => axios({
    url: '/blog/all',
    method: 'get',
    data
});

export const createBlog = data => axios({
    url: '/blog/add',
    method: 'post',
    data
})

export const createImageBlog = data => axios({
    url: '/blog/addImg',
    method: 'post',
    data,
    headers: {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }
})

export const updateCommonBlog = (blog_id, data) => axios({
    url: '/blog/' + blog_id,
    method: 'put',
    data
})

export const updateImageBlog = (blog_id, data) => axios({
    url: '/blog/' + blog_id,
    method: 'post',
    _method: 'put',
    data: data,
    headers: {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }
})

export const deleteBlog = (blog_id, data) => axios({
    url: '/blog/' + blog_id,
    method: 'delete',
    data
})


/* community */

export const joinCommunityToggle = (community_id, data={}) => axios({
    url: '/community/change_status/' + community_id,
    method: 'get',
    data
})

export const getALlCommunity = data => axios({
    url: '/community/all',
    method: 'get',
    data
})

export const getMyCommunity = data => axios({
    url: '/community/my',
    method: 'get',
    data
})


/* comment */

export const createComment = data => axios({
    url: '/comment/add',
    method: 'post',
    data
})

export const showComment = (blog_id, data={}) => axios({
    url: '/comment/show/' + blog_id,
    method: 'get',
    data
})

export const getCommentsByBlogId = (blog_id, data={}) => axios({
    url: `/blog/`+blog_id+`/comment`,
    method: 'get',
    data
})

export const createSubComment = data => axios({
    url: '/comment/add_sub',
    method: 'post',
    data
})

export const editComment = (comment_id, data={}) => axios({
    url: '/comment/' + comment_id + '/edit',
    method: 'get',
    data
})

export const deleteComment = (comment_id, data={}) => axios({
    url: '/comment/' + comment_id,
    method: 'delete',
    data
})

export const updateComment = (comment_id, data) => axios({
    url: '/comment/' + comment_id,
    method: 'put',
    data
})

/* User */

export const getProfile = (data) => axios({
    url: '/user/profile',
    method: 'get',
    data
})