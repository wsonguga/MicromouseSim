#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>
#include <string.h>

int main (int argc, char *argv[])
{
    setuid (0);

     /* WARNING: Only use an absolute path to the script to execute,
      *          a malicious user might fool the binary and execute
      *          arbitary commands if not.
      * */
    if (strcmp(argv[1], "save") == 0)
        system ("/bin/sh /tmp/micromouse/maze.sh");
    else if (strcmp(argv[1], "run") == 0) {
        char strCmd[100];
        char *strCmdOrigin = "/bin/sh /tmp/micromouse/runStrategy.sh ";
        strcpy(strCmd, strCmdOrigin);
        strcpy(strCmd+strlen(strCmdOrigin), argv[2]);
        system (strCmd);
    }
    else if (strcmp(argv[1], "stop") == 0) {
        char strCmd[100];
        char *strCmdOrigin = "/bin/sh /tmp/micromouse/stopStrategy.sh ";
        strcpy(strCmd, strCmdOrigin);
        strcpy(strCmd+strlen(strCmdOrigin), argv[2]);
        system (strCmd);
    }

   return 0;
}