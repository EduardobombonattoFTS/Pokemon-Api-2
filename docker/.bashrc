# ~/.bashrc: executed by bash(1) for non-login shells.

# Note: PS1 and umask are already set in /etc/profile. You should not
# need this unless you want different defaults for root.
# PS1='${debian_chroot:+($debian_chroot)}\h:\w\$ '
# umask 022

# You may uncomment the following lines if you want `ls' to be colorized:
# export LS_OPTIONS='--color=auto'
# eval "$(dircolors)"
alias ls='ls $LS_OPTIONS'
alias ll='ls $LS_OPTIONS -l'
alias l='ls $LS_OPTIONS -lA'
#
# Some more alias to avoid making mistakes:
# alias rm='rm -i'
# alias cp='cp -i'
# alias mv='mv -i'

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion


# Alias Project Laravel
alias apache-start='sudo /etc/init.d/apache2 start'
alias apache-stop='sudo /etc/init.d/apache2 stop'
alias apache-restart='sudo /etc/init.d/apache2 restart'
alias apache-reload='sudo /etc/init.d/apache2 reload'
alias clr='php artisan optimize:clear && php artisan optimize'
alias cls='php artisan optimize:clear && php artisan optimize'
alias cr='php artisan optimize:clear && php artisan optimize'
alias l:c='php artisan optimize:clear && php artisan optimize'
alias l:clear='php artisan optimize:clear && php artisan optimize'
alias l:mfs='php artisan migrate:fresh --seed'
alias l:migrate='php artisan migrate'
alias l:ms='php artisan migrate --seed'
alias l:mseed='php artisan migrate --seed'
alias l:r='php artisan route:list'
alias l:rc='php artisan route:clear'
alias l:route='php artisan route:list'
alias l:route-clear='php artisan route:clear'
alias laravel='php artisan'
alias laravel:clear='php artisan optimize:clear && php artisan optimize'
alias laravel:mfs='php artisan migrate:fresh --seed'
alias laravel:migrate='php artisan migrate'
alias laravel:ms='php artisan migrate --seed'
alias laravel:mseed='php artisan migrate --seed'
alias laravel:r='php artisan route:list'
alias laravel:rc='php artisan route:clear'
alias laravel:route='php artisan route:list'
alias laravel:route-clear='php artisan route:clear'
alias a='php artisan'
alias pa='php artisan'
alias pa:c='php artisan optimize:clear && php artisan optimize'
alias pa:clear='php artisan optimize:clear && php artisan optimize'
alias pa:mfs='php artisan migrate:fresh --seed'
alias pa:migrate='php artisan migrate'
alias pa:ms='php artisan migrate --seed'
alias pa:mseed='php artisan migrate --seed'
alias pa:r='php artisan route:list'
alias pa:rc='php artisan route:clear'
alias pa:route='php artisan route:list'
alias pa:route-clear='php artisan route:clear'
alias gs='git status'
alias ga='git add'
alias gall='git add *'
alias gcm='git commit -m'
alias gp='git push'
alias gcb='git checkout -b'
alias gc='git checkout'
alias gb='git branch'
alias c='composer'
alias ci='composer install'
alias cu='composer update'
alias cda='composer dump-autoload'
alias crd='sudo rm -R vendor/'
alias n='npm'
alias ni='npm install'
alias nu='npm update'
alias ndev='npm run dev'
alias nstart='npm run start'
alias nwatch='npm run watch'
alias nprod='npm run prod'
alias nbuild='npm run build'
alias nrd='sudo rm -R node_modules/'
alias apachown='sudo chown -R www-data:www-data'
alias chwn='sudo chown -R www-data:www-data'
alias vsc="code ."
alias projects="docker exec -it project bash"
