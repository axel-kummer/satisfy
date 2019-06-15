#!/usr/bin/env bash

GIT_BRANCH=$( git rev-parse --abbrev-ref HEAD)

if [[ -z ${GIT_BRANCH} ]]; then
    GIT_BRANCH="master"
fi

export GIT_BRANCH