const fileSize = (size: number): string => {
  if (size >= 1000000) {
    return Math.floor((size / 1000000) * 10) / 10 + 'MB'
  } else if (size >= 1000) {
    return Math.floor((size / 1000) * 10) / 10 + 'KB'
  } else {
    return size + ' bytes'
  }
}

export {fileSize}
