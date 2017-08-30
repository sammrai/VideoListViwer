import sys
import test

if len(sys.argv)==3:
	test.kill_process(sys.argv[1],sys.argv[2])
if len(sys.argv)==2:
	test.refresh(sys.argv[1])
