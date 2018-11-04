<nav id="main-nav" role="navigation">

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-mint">
        <li><a href="{{ url('home') }}">Home</a></li>

        <li><a href="javascript:void(0)">AUTHORIZATION</a>
            <ul>
                <li><a href="userIndex">Add New User</a></li>
                <li><a href="userPrivilegeIndex">Set User Previlege</a></li>
                <li><a href="changePassword">Change User Password</a></li>
                {{--<li><a href="javascript:void(0)">Reset Default Password</a></li>--}}
            </ul>
        </li>

        <li><a href="javascript:void(0)">PRODUCTS</a>
            <ul>
                <li><a href="categoryIndex">Product Category</a></li>
                <li><a href="subcategoryIndex">Product Sub Category</a></li>
                <li><a href="productBrandIndex">Product Brands</a></li>
                <li><a href="productUnitIndex">Product Units</a></li>
                <li><a href="productSizeIndex">Product Sizes</a></li>
                <li><a href="productModelIndex">Product Models</a></li>
                <li><a href="productTaxIndex">List of Taxes</a></li>
                <li><a href="taxGroupIndex">Taxes Group</a></li>
                <li><a href="godownIndex">Godown Info</a></li>
                <li><a href="rackIndex">Rack Info</a></li>
                <li><a href="relationshipIndex">Customers / Suppliers</a></li>
                <li><a href="productIndex">Product Details</a></li>
            </ul>
        </li>


        <li><a href="javascript:void(0)">REQUISITION</a>
            <ul>
                <li><a href="javascript:void(0)">REQUISITION</a>
                    <ul>
                        <li><a href="{!! url('requisition.create.index') !!}">Create Requisition</a></li>
                        <li><a href="{!! route('requisition.edit.index') !!}">Edit Requisition</a></li>
                        <li><a href="{!! route('requisition.approve.index') !!}">Approve Requisition</a></li>
                    </ul>
                </li>
                <li><a href="javascript:void(0)">DELIVERY FOR CONSUMPTION</a>
                    <ul>
                        {{--<li><a href="{!! url('requisition.create.index') !!}">Create Requisition</a></li>--}}
                        {{--<li><a href="{!! route('requisition.edit.index') !!}">Edit Requisition</a></li>--}}
                        {{--<li><a href="{!! route('requisition.approve.index') !!}">Approve Requisition</a></li>--}}
                    </ul>
                </li>
                <li><a href="javascript:void(0)">REPORTS</a>
                    <ul>
                        {{--<li><a href="{!! url('requisition.create.index') !!}">Create Requisition</a></li>--}}
                        {{--<li><a href="{!! route('requisition.edit.index') !!}">Edit Requisition</a></li>--}}
                        {{--<li><a href="{!! route('requisition.approve.index') !!}">Approve Requisition</a></li>--}}
                    </ul>
                </li>
            </ul>


            {{--<ul>--}}
                {{--<li><a href="{!! url('requisition.create.index') !!}">Create Requisition</a></li>--}}
                {{--<li><a href="{!! route('requisition.edit.index') !!}">Edit Requisition</a></li>--}}
                {{--<li><a href="{!! route('requisition.approve.index') !!}">Approve Requisition</a></li>--}}
            {{--</ul>--}}
        </li>


        {{--<li><a href="javascript:void(0)">Download</a></li>--}}
        <li><a href="javascript:void(0)">PURCHASE</a>
            <ul>

                <li><a href="javascript:void(0)">PURCHASE</a>
                    <ul>
                        <li><a href="purchaseOrderIndex">Purchase Products</a></li>
                        <li><a href="editpurchaseindex">Edit Purchase</a></li>
                        <li><a href="#">Approve Purchase</a></li>
                    </ul>
                </li>

                <li><a href="javascript:void(0)">RECEIVES</a>
                    <ul>
                        <li><a href="#">Receive Products</a></li>
                        <li><a href="#">Edit Receive</a></li>
                        <li><a href="#">Approve Receive</a></li>
                    </ul>
                </li>

                <li><a href="javascript:void(0)">REPORTS</a>
                    <ul>
                        <li><a href="#">Receive Products</a></li>
                        <li><a href="#">Edit Receive</a></li>
                        <li><a href="#">Approve Receive</a></li>
                    </ul>
                </li>

            </ul>
        </li>


        <li><a href="javascript:void(0)">SALES</a>
            <ul>
                <li><a href="invoicePreviewIndex">Invoice Preview</a></li>

                <li><a href="javascript:void(0)">SALES</a>
                    <ul>
                        <li><a href="salesinvoiceindex">Sales Invoice</a></li>
                        <li><a href="#">Edit Sales Invoice</a></li>
                        <li><a href="approveinvoiceindex">Approve Sales Invoice</a></li>
                    </ul>
                </li>

                <li><a href="javascript:void(0)">DELIVERY</a>
                    <ul>
                        <li><a href="deliveryinvoiceindex">Delivery Products</a></li>
                        <li><a href="#">Edit Delivery Products</a></li>
                        <li><a href="#">Approve Delivery Products</a></li>
                    </ul>
                </li>


                <li><a href="javascript:void(0)">REPORTS</a>
                    <ul>
                        <li><a href="rptinvoiceindex">Preview Print Invoice</a></li>
                        <li><a href="rptdeliverychallanindex">Print Delivery Challan</a></li>
                    </ul>
                </li>


            </ul>
        </li>

        {{--<li><a href="javascript:void(0)">Docs</a></li>--}}

        <li><a href="javascript:void(0)">REPORTS</a>
            <ul>
                <li><a href="{!! route('report.dailytrans.rpt') !!}">Daily Transaction List</a></li>
                <li><a href="{!! route('report.dailyvoucher.rpt') !!}">View Print Voucher</a></li>

                {{--<li><a href="javascript:void(0)">Unposted</a>--}}
                {{--<ul>--}}
                {{--<li><a href="{!! route('report.account.tb') !!}">Trial Ballance AC</a></li>--}}
                {{--<li><a href="{!! route('report.group.tb') !!}">Trial Ballance Group</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}

                <li><a href="javascript:void(0)">Trial Balance</a>
                    <ul>
                        <li><a href="{!! route('posted.account.tb') !!}">Trial Ballance</a></li>
                        <li><a href="{!! route('posted.group.tb') !!}">Trial Ballance Group</a></li>
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

                {{--<li><a href="test.view">test</a></li>--}}
            </ul>
        </li>

    </ul>
    <br/>
</nav>