g_global = -1;
function inv_toggle(num, togg)
{
    if(g_global != num && g_global != -1)
        inv_toggle(g_global, 0);
    g_global = num;
    if(0==togg)
    {
        document.getElementById("obj_f_" + num).style.display="none";
        document.getElementById("obj_1_" + num).style.display="none";
        document.getElementById("obj_0_" + num).style.display="block";
    }
    else
    {
        document.getElementById("obj_0_" + num).style.display="none";
        document.getElementById("obj_1_" + num).style.display="block";
        document.getElementById("obj_f_" + num).style.display="block";
    }
    return false;
}
function get_amuont(num)
{
    function inRange(start, end, value)
    {
        if (value <= end && value >= start)
            return value;
        return undefined;
    }
    var amount = parseInt(document.getElementById("amount_" + num).value);
    var tmp_amount = 0;
    var final;

    if (amount == 0)
    {
        return '0';
    }
    else
    {
        tmp_amount = amount%10;
    }
    switch(tmp_amount)
    {
        case 1:
            if (amount == 11)
            {
                final = amount+' предметов';
            }
            else
            {
                final = amount+' предмет';
            }
            break;
        case inRange(2,4,tmp_amount):
            if (amount < 15 && amount > 11)
            {
                final = amount+' предметов';
            }
            else
            {
                final = amount+' предмета';
            }
            break;
        case inRange(5,9,tmp_amount):
        case 0:
            final = amount+' предметов';
            break;
    }
    return final;
}
function get_cost(num,price)
{
    var amount = parseInt(document.getElementById("amount_" + num).value);
    return amount*price;
}	