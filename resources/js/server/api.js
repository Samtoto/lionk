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
    url: '/blog/index',
    method: 'get',
    data
});

export const createBlog = data => axios({
    url: '/blog/create',
    method: 'post',
    data
})

export const createImageBlog = data => axios({
    url: '/blog/createImg',
    method: 'post',
    data,
    headers: {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }
})

export const updateCommonBlog = (blog, data) => axios({
    url: '/blog/' + blog,
    method: 'put',
    data
})

export const updateImageBlog = (blog, data) => axios({
    url: '/blog/' + blog,
    method: 'post',
    _method: 'put',
    data: data,
    headers: {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }
})

export const deleteBlog = (blog, data) => axios({
    url: '/blog/' + blog,
    method: 'delete',
    data
})


/* community */

export const joinCommunityToggle = (community, data={}) => axios({
    url: '/community/' + community + '/subscribe',
    method: 'get',
    data
})

export const getALlCommunity = data => axios({
    url: '/community/index',
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
    url: '/comment/create',
    method: 'post',
    data
})

export const showComment = (blog, data={}) => axios({
    url: '/comment/show/' + blog,
    method: 'get',
    data
})

export const getCommentsByBlogId = (blog, data={}) => axios({
    url: `/blog/`+blog+`/comment`,
    method: 'get',
    data
})

export const createSubComment = data => axios({
    url: '/comment/create',
    method: 'post',
    data
})

export const editComment = (comment, data={}) => axios({
    url: '/comment/' + comment + '/edit',
    method: 'get',
    data
})

export const deleteComment = (comment, data={}) => axios({
    url: '/comment/' + comment,
    method: 'delete',
    data
})

export const updateComment = (comment, data) => axios({
    url: '/comment/' + comment,
    method: 'put',
    data
})

/* User */

export const getProfile = (data) => axios({
    url: '/user/profile',
    method: 'get',
    data
})