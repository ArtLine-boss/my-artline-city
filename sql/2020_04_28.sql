UPDATE operations SET operations.OperationType=3 WHERE operations.OperationType=1;
UPDATE operations SET operations.OperationType=1 WHERE operations.OperationType=0 AND `OPERATION_NAME` = 'Печать';