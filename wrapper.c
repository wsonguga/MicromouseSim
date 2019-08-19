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
    // argv[1]: command
    // argv[2]: userId
    // argv[3]: sessionId

    char tmpCmd[100];
    char phpRootPath[100];
    char demoPath[100];
    char *rootPath = "/tmp/micromouse/";

    demoPath[0] = 0;

    // chmod +x /tmp/micromouse/XXXXX/demo_core.py
    strcpy(demoPath, rootPath);
    strcpy(demoPath+strlen(rootPath), argv[2]);
    strcpy(demoPath+strlen(rootPath)+strlen(argv[2]), "/demo_core.py");

    tmpCmd[0] = 0;
    strcpy(tmpCmd, "chmod +x ");
    strcpy(tmpCmd+strlen(tmpCmd), demoPath);
    system(tmpCmd);

    // start a new session using batch mode without running anything
    // argv[2] = daemonPort
    if (strcmp(argv[1], "session") == 0) {
        tmpCmd[0] = 0;
        strcpy(tmpCmd, "/bin/sh /tmp/micromouse/maze.sh ");
        strcpy(tmpCmd+strlen(tmpCmd), argv[2]);
        system(tmpCmd);
    }
    // coresendmsg to run demo_core.py through runStrategy.sh
    // argv[2] = userId: 1-50
    // argv[3] = sessionId: CORE Session
    else if (strcmp(argv[1], "run") == 0) {
        char strCmd[100];
        strCmd[0] = 0;
        char *strCmdOrigin = "/bin/sh /tmp/micromouse/runStrategy.sh ";
        strcpy(strCmd, strCmdOrigin);
        strcpy(strCmd+strlen(strCmdOrigin), argv[2]);
        strcat(strCmd, " ");
        strcat(strCmd, argv[3]);
        system (strCmd);
    }
    // coresendmsg to run killp.sh (kill process) through stopStrategy.sh
    // argv[2] = userId: 1-50
    // argv[3] = sessionId: CORE Session
    else if (strcmp(argv[1], "stop") == 0) {
        char strCmd[100];
        strCmd[0] = 0;
        char *strCmdOrigin = "/bin/sh /tmp/micromouse/stopStrategy.sh ";
        strcpy(strCmd, strCmdOrigin);
        strcpy(strCmd+strlen(strCmdOrigin), argv[2]);
        strcat(strCmd, " ");
        strcat(strCmd, argv[3]);
        system (strCmd);
    }

   return 0;
}