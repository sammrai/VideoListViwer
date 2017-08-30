import sys
import numpy as np
 
#---------------------------------
def calc_csv( fpath_csv, cmd ):
    data = np.loadtxt( fpath_csv, delimiter=',' )
    if cmd == 1:
        ret = data.sum()
    elif cmd == 2:
        ret = data.mean()
    elif cmd == 3:
        ret = data.max()
    elif cmd == 4:
        ret = data.min()
    else:
        print "invalid command"
        ret = 0
    return ret
 
#---------------------------------
# argv[1] : file path
# argv[2] : command 1:sum, 2:average, 3:max, 4:min
if __name__ == '__main__':
    ary_argv = sys.argv
    fpath = ary_argv[1]
    cmd   = int(ary_argv[2])
    print calc_csv( fpath, cmd )