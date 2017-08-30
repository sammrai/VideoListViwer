import sys,os
import signal
print "kill",sys.argv[1]
try:
	os.kill(sys.argv[1], signal.SIGKILL)
	print "0"
except:
	print "1"
