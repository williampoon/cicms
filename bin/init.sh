#!/bin/bash

root=$(cd "$(dirname "$0")"; cd ..; pwd)
cd $root

# 需要创建的目录
mk_dirs=(
    application/logics
    application/models
    application/services
    application/views/layout
    application/views_compile
    build
    storage/logs
    storage/cache
    storage/session
)

# 创建目录并设定权限
for dir in ${mk_dirs[@]}; do
    if [ ! -d $dir ]; then
        mkdir -p $dir
        echo "Created Directory: $dir"
    fi
done

# 清空视图缓存文件
rm -f application/cache/*
rm -f storage/cache/*
