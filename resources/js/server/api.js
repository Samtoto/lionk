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
        console.log('success')
        return Promise.resolve(response);
    } else {
        return Promise.reject(response)
    }
}, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
});


export const getAllBlogs = data => axios({
    url: '/blog/all',
    method: 'get',
    data
});

export const joinCommunityToggle = (community_id, data={}) => axios({
    url: '/community/change_status/' + community_id,
    method: 'get',
    data
})