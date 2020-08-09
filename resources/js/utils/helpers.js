function datetimeFormatter(date) {
    let d = new Date(date)
    let now = new Date()
    // document.write(Math.floor((now - d) / 1000))
    let diff = now - d;
    if (diff < 60000) {
        return Math.floor(diff / 1000) + ' secs ago'
    } else if (diff < 3600000) {
        return Math.floor(diff / 60000) + ' mins ago'
    } else if (diff < 3600000 * 24) {
        return Math.floor(diff / 3600000) + ' hours ago'
    } else if (diff < 3600000 * 24 * 30) {
        return Math.floor(diff / 3600000 / 24) + ' days ago'
    } else {
        return d.toLocaleString()
    }

}

export const timeFormatter = datetimeFormatter;