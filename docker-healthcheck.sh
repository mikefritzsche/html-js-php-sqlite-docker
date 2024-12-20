#!/bin/bash
if curl -f http://localhost/ >/dev/null 2>&1; then
    exit 0
else
    exit 1
fi
