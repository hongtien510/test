#!/usr/bin/env python2
import sys, fp

# get code from arguments
if len(sys.argv) > 1:
    code = sys.argv[1]
else:
    print("-1")

decoded = fp.decode_code_string(code)
result = fp.best_match_for_query(decoded)
if result.TRID:
    print(result.metadata.get("track"))
else:
    print("0")