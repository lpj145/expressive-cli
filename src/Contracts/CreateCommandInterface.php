<?php

namespace mdantas\ExpressiveCli\Contracts;


interface CreateCommandInterface
{
    public const HELP_ARGS_CLASS = <<< EOT
Fully qualified class name of the class for which to create a factory.
This value should be quoted to ensure namespace separators are not
interpreted as escape sequences by your shell. The class should be
autoloadable.
EOT;

    public const HELP_COMMAND = <<< EOT
%s Mynamespace/full/qualified
EOT;


}
