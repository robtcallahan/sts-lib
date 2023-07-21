################################################################################
# define the target os so that I can build on my Mac and install on RedHat or CentOS
%define _target_os linux

# define the installation directory
%define toplevel_dir /opt
%define install_target %{toplevel_dir}/%{name}

# doc_root will get a pointer to the install target 
%define doc_root /var/www/html

# cron files get installed here
%define cron_dir /etc/cron.d

# main definitions here
Summary:   STS-LIB - Common PHP Classes for STS Tools
Name:      %{name}
Version:   %{version}
Release:   %{release}
#BuildRoot: %{_topdir}/buildroot
%define buildroot %{_topdir}/buildroot
BuildArch: noarch
License:   Neustar/Restricted
Group:     Neustar/STS

# RPM_BUILD_DIR = pkg/rpmbuild/BUILD
# RPM_BUILD_ROOT = pkg/rpmbuild/buildroot

################################################################################
%description
STS-LIB - Common PHP Classes for STS Tools


################################################################################
%prep

#echo "Phing.log"
#echo "_topdir=%{_topdir}"
#echo "toplevel_dir=%{toplevel_dir}"
#echo "install_target=%{install_target}"
#echo "buildroot=%{buildroot}"
#echo "RPM_BUILD_DIR=$RPM_BUILD_DIR"
#echo "RPM_BUILD_ROOT=$RPM_BUILD_ROOT"

################################################################################
%install

export RPM_BUILD_DIR=`pwd`

# create build root
mkdir -p $RPM_BUILD_ROOT/%{install_target}

# copy files to build root
cp -R * $RPM_BUILD_ROOT/%{install_target}

# Tag the release and version info into the ABOUT file
echo 'STS-Lib Version %{version}-%{release}, Built %{release_name}' > $RPM_BUILD_ROOT/%{install_target}/ABOUT

################################################################################
%clean
rm -rf $RPM_BUILD_ROOT


################################################################################
%pre
if [ -n "$1" ]; then
    if [ $1 -eq 1 ]; then
        # initial install action here
        echo "Executing pre install actions"
    elif [ $1 -eq 2 ]; then
        # upgrade action here
        echo "Executing pre upgrade actions"
    fi
fi


################################################################################
%post
if [ -n "$1" ]; then
    if [ $1 -eq 1 ]; then
        # initial install action here
        echo "Executing post install actions"
    elif [ $1 -eq 2 ]; then
        # upgrade action here
        echo "Executing post upgrade actions"
    fi
fi

# create a sym link to /opt
if [ ! -h %{doc_root}/%{name} ]; then
    ln -s %{install_target} %{doc_root}
fi

# clean up unnecessary files
if [ -e %{install_target}/build.xml ]; then
    rm -f %{install_target}/build.xml
fi
if [ -e %{install_target}/%{name}.spec ]; then
    rm -f %{install_target}/%{name}.spec
fi

#########################################
%verifyscript


################################################################################
%preun

# do we have a non-empty param 1?
if [ -n "$1" ]; then
    # yep, check the value
    if [ $1 -eq 0 ]; then
        # uninstall action here
        echo "Executing preun uninstall actions"
    elif [ $1 -eq 1 ]; then
        # upgrade action here
        echo "Executing preun upgrade actions"
    fi
fi


################################################################################
%postun
if [ -n "$1" ]; then
    if [ $1 -eq 0 ]; then
        # uninstall action here
        echo "Executing postun uninstall actions"
    elif [ $1 -eq 1 ]; then
        # upgrade action here
        echo "Executing postun upgrade actions"
    fi
fi


################################################################################

%files
%defattr(-,root,root,-)

# directory perms
%attr(755,root,root) %{install_target}
%attr(755,root,root) %{install_target}/bin
%attr(755,root,root) %{install_target}/config
%attr(755,root,root) %{install_target}/STS
%attr(755,root,root) %{install_target}/STS/AD
%attr(755,root,root) %{install_target}/STS/CMDB
%attr(755,root,root) %{install_target}/STS/CMDB/config
%attr(755,root,root) %{install_target}/STS/Database
%attr(755,root,root) %{install_target}/STS/DB
%attr(755,root,root) %{install_target}/STS/HPSIM
%attr(755,root,root) %{install_target}/STS/LDAP
%attr(755,root,root) %{install_target}/STS/Login
%attr(755,root,root) %{install_target}/STS/OpsCenter
%attr(755,root,root) %{install_target}/STS/SANScreen
%attr(755,root,root) %{install_target}/STS/SystemsDirector
%attr(755,root,root) %{install_target}/STS/Util

# File perms
%attr(644,root,root) %{install_target}/ABOUT
%attr(755,root,root) %{install_target}/bin/*
%attr(644,root,root) %{install_target}/config/*
%attr(644,root,root) %{install_target}/STS/*.php

%attr(644,root,root) %{install_target}/STS/AD/*
%attr(644,root,root) %{install_target}/STS/CMDB/*.php
%attr(644,root,root) %{install_target}/STS/CMDB/config/*
%attr(644,root,root) %{install_target}/STS/Database/*
%attr(644,root,root) %{install_target}/STS/DB/*
%attr(644,root,root) %{install_target}/STS/HPSIM/*
%attr(644,root,root) %{install_target}/STS/LDAP/*
%attr(644,root,root) %{install_target}/STS/Login/*
%attr(644,root,root) %{install_target}/STS/OpsCenter/*
%attr(644,root,root) %{install_target}/STS/SANScreen/*
%attr(644,root,root) %{install_target}/STS/SNCache/*
%attr(644,root,root) %{install_target}/STS/SystemsDirector/*
%attr(644,root,root) %{install_target}/STS/Util/*
