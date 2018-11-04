<nav id="main-nav" role="navigation">

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-blue">
        <li><a href="{{ url('home') }}">Home</a></li>

        <li><a href="javascript:void(0)">Authorization</a>
            <ul>
                    <?php if (\Auth::user()->role == 'Admin'): ?>
                <li><a href="userIndex">Add New User</a></li>
                <li><a href="userPrivilegeIndex">Set User Previlege</a></li>
                        <?php endif; ?> 
                <li><a href="changePassword">Change User Password</a></li>
                {{--<li><a href="javascript:void(0)">Reset Default Password</a></li>--}}
            </ul>
        </li>

        <li><a href="javascript:void(0)">Basic</a>
            <ul>

         <?php if (\Auth::user()->role == 'Admin'): ?>
                <li><a href="companyConfigIndex">Company Settings</a></li>
                <li><a href="projectIndex">Projects</a></li>
                <li><a href="fiscalPeriodIndex">Fiscal Period</a></li>
                <li><a href="accountGroupIndex">Account Groups</a></li>
                <li><a href="accountHeadIndex">Account Heads</a></li>
                <li><a href="openingBalanceIndex">Opening Balance</a></li>
                <li><a href="depreciationIndex">Fixed Asset Depreciation</a></li>
                <li><a href="anualBudgetIndex">Anual Budget To Expense</a></li>
        <?php endif; ?>
                <li><a href="{!! url('yearclose/index') !!}">Year Close</a></li>
            </ul>
        </li>

        {{--<li><a href="javascript:void(0)">Download</a></li>--}}
        <li><a href="javascript:void(0)">Payments</a>
            <ul>
                <li><a href="javascript:void(0)">Payments  Mode</a>
                    <?php if (\Auth::user()->role == 'Admin'): ?>
                    <ul>
                        <li><a href="cashPaymentIndex">Cash Payments</a></li>
                        <li><a href="bankPaymentIndex">Bank Payments</a></li>
                    </ul>
                    <?php endif; ?>
                </li>



                <li><a href="javascript:void(0)">Receives</a>
                <?php if (\Auth::user()->role == 'Admin'): ?>
                    <ul>
                        <li><a href="cashReceiveIndex">Cash Receive</a></li>
                        <li><a href="bankReceiveIndex">Bank Receive</a></li>
                    </ul>
                <?php endif; ?>
                </li>




                    <?php if (\Auth::user()->role == 'Admin'): ?> <li><a href="journalIndex">Payments</a></li><?php endif; ?>
    <?php if (\Auth::user()->role == 'Admin'): ?><li><a href="{!! route('transaction.edit.index') !!}">Edit Unposted Budget</a></li>
                    <?php endif; ?>

            <?php if (\Auth::user()->role == 'Admin'): ?>
                <li><a href="{!! route('transaction.post.index') !!}">Check & Approve Budget</a></li>
            <?php endif; ?>

            </ul>
        </li>
            <?php if (\Auth::user()->role =='Admin'):?>
        <li><a href="javascript:void(0)">Monitor</a>
            <ul>
                <li><a href="userIndex">Projects</a></li>
                <li><a href="userPrivilegeIndex">Budget</a></li>
                <!--<li><a href="changePassword">Change User Password</a></li>-->
            </ul>
        </li>
            <?php endif ?>


        {{--<li><a href="javascript:void(0)">Docs</a></li>--}}

        <li><a href="javascript:void(0)">Reports</a>
        <?php if (\Auth::user()->role == 'Admin'): ?>
            <ul>
                <li><a href="{!! route('report.dailytrans.rpt') !!}">Daily Transaction List</a></li>
                <li><a href="{!! route('report.dailyvoucher.rpt') !!}">View Print Budget</a></li>

                {{--<li><a href="javascript:void(0)">Unposted</a>--}}
                    {{--<ul>--}}
                        {{--<li><a href="{!! route('report.account.tb') !!}">Trial Balance AC</a></li>--}}
                        {{--<li><a href="{!! route('report.group.tb') !!}">Trial Balance Group</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}

                <li><a href="javascript:void(0)">Trial Balance</a>
                    <ul>
                        <li><a href="{!! route('posted.account.tb') !!}">Trial Balance</a></li>
                        <li><a href="{!! route('posted.group.tb') !!}">Trial Balance Group</a></li>
                    </ul>
                </li>
                <li><a href="{!! route('general.ledger.index') !!}">General Ledger</a></li>

                
                <li><a href="javascript:void(0)">Register</a>
                    <ul>
                        <li><a href="{!! route('cash.register.index') !!}">Cash Register</a></li>
                        <li><a href="{!! route('bank.register.index') !!}">Bank Register</a></li>
                    </ul>
                </li>
                {{--<li><a href="{!! route('cash.register.index') !!}">Cash Register</a></li>--}}
                {{--<li><a href="{!! route('bank.register.index') !!}">Bank Register</a></li>--}}

                <!--<li><a href="test.view">test</a></li> -->
            </ul>
    <?php endif; ?>
        </li>

        <li><a href="javascript:void(0)">Statements</a>
    <?php if (\Auth::user()->role == 'Admin'): ?>
            <ul>
                <li><a href="{!! route('fn.statement.add') !!}">Add Budget</a></li>
                <li><a href="{!! route('fn.statement.create') !!}">Create Budget Data</a></li>
                <li><a href="{!! route('fn.statement.process') !!}">Process Budget Data</a></li>
                <li><a href="{!! route('fn.statement.print') !!}">Print Budget Statement</a></li> 
            </ul>
    <?php endif; ?>
        </li>

@if(has_inventory()==true)
        <li><a href="{!! route('inventoryHome') !!} ">Inventory</a>
        </li>
@endif
        {{--<li><a href="javascript:void(0)">Mega menu</a>--}}
            {{--<ul class="mega-menu">--}}
                {{--<li>--}}
                    {{--<!-- The mega drop down contents -->--}}
                    {{--<div style="width:400px;max-width:100%;">--}}
                        {{--<div style="padding:5px 24px;">--}}
                            {{--<p>This is a mega drop down test. Just set the "mega-menu" class to the parent UL element to inform the SmartMenus script. It can contain <strong>any HTML</strong>.</p>--}}
                            {{--<p>Just style the contents as you like (you may need to reset some SmartMenus inherited styles - e.g. for lists, links, etc.)</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<!--end Mega Menu-->--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</li>--}}


    </ul>
    <br/>
</nav>