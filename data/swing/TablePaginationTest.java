//package example;
//-*- mode:java; encoding:utf8n; coding:utf-8 -*-
// vim:set fileencoding=utf-8:
//@homepage@
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.event.*;
import javax.swing.table.*;
import javax.swing.text.*;
import javax.swing.plaf.basic.*;
import java.util.*;

public class TablePaginationTest {
    private static final Color evenColor = new Color(240, 255, 250);
    private static final LinkViewRadioButtonUI ui = new LinkViewRadioButtonUI();
    private static int LR_PAGE_SIZE = 5;
    private final Box box = Box.createHorizontalBox();
    private final String[] columnNames = {"Year", "String", "Comment"};
    private final DefaultTableModel model = new DefaultTableModel(null, columnNames) {
        @Override public Class<?> getColumnClass(int column) {
            return (column==0)?Integer.class:Object.class;
        }
    };
    private final TableRowSorter<TableModel> sorter = new TableRowSorter<TableModel>(model) {
        @Override public void toggleSortOrder(int column) {
            RowFilter<? super TableModel, ? super Integer> f = getRowFilter();
            setRowFilter(null);
            super.toggleSortOrder(column);
            setRowFilter(f);
        }
    };
    private final JTable table = new JTable(model) {
        @Override public Component prepareRenderer(TableCellRenderer tcr, int row, int column) {
            Component c = super.prepareRenderer(tcr, row, column);
            if(isRowSelected(row)) {
                c.setForeground(getSelectionForeground());
                c.setBackground(getSelectionBackground());
            }else{
                c.setForeground(getForeground());
                c.setBackground((row%2==0)?evenColor:getBackground());
            }
            return c;
        }
    };
    public JComponent makeUI() {
        table.setFillsViewportHeight(true);
        table.setIntercellSpacing(new Dimension());
        table.setShowGrid(false);
        //table.setShowHorizontalLines(false);
        //table.setShowVerticalLines(false);
        table.putClientProperty("terminateEditOnFocusLost", Boolean.TRUE);
        table.setRowSorter(sorter);
        sorter.setSortKeys(Arrays.asList(new RowSorter.SortKey(0, SortOrder.ASCENDING)));
        sorter.setSortable(1, false);
        sorter.setSortable(2, false);

        for(int i=0;i<=2008;i++) {
            model.addRow(new Object[] {i, "Test: "+i, (i%2==0)?"":"comment..."});
        }
        initLinkBox(100, 1);

        JPanel p = new JPanel(new BorderLayout());
        p.add(box, BorderLayout.NORTH);
        p.add(new JScrollPane(table));
        return p;
    }
    private void initLinkBox(final int itemsPerPage, final int currentPageIndex) {
        //assert currentPageIndex>0;
        sorter.setRowFilter(null);
        //sorter.sort();
        //((AbstractTableModel)table.getModel()).fireTableDataChanged();
        sorter.allRowsChanged();
        sorter.setRowFilter(makeRowFilter(itemsPerPage, currentPageIndex-1));

        ArrayList<JRadioButton> l = new ArrayList<JRadioButton>();

        int startPageIndex = currentPageIndex-LR_PAGE_SIZE;
        if(startPageIndex<=0) startPageIndex = 1;

        int maxPageIndex = (model.getRowCount()/itemsPerPage)+1;
        int endPageIndex = currentPageIndex+LR_PAGE_SIZE-1;
        if(endPageIndex>maxPageIndex) endPageIndex = maxPageIndex;

        if(currentPageIndex>1)
          l.add(makePNRadioButton(itemsPerPage, currentPageIndex-1, "Prev"));
        for(int i=startPageIndex;i<=endPageIndex;i++)
          l.add(makeRadioButton(itemsPerPage, currentPageIndex, i-1));
        if(currentPageIndex<maxPageIndex)
          l.add(makePNRadioButton(itemsPerPage, currentPageIndex+1, "Next"));

        box.removeAll();
        ButtonGroup bg = new ButtonGroup();
        box.add(Box.createHorizontalGlue());
        for(JRadioButton r:l) {
            box.add(r); bg.add(r);
        }
        box.add(Box.createHorizontalGlue());
        box.revalidate();
        box.repaint();
        l.clear();
    }

    private JRadioButton makeRadioButton(final int itemsPerPage, final int current, final int target) {
        JRadioButton radio = new JRadioButton(""+(target+1)) {
            @Override protected void fireStateChanged() {
                ButtonModel model = getModel();
                if(!model.isEnabled()) {
                    setForeground(Color.GRAY);
                }else if(model.isPressed() && model.isArmed()) {
                    setForeground(Color.GREEN);
                }else if(model.isSelected()) {
                    setForeground(Color.RED);
                //}else if(isRolloverEnabled() && model.isRollover()) {
                //    setForeground(Color.BLUE);
                }
                super.fireStateChanged();
            }
        };
        radio.setForeground(Color.BLUE);
        radio.setUI(ui);
        if(target+1==current) {
            radio.setSelected(true);
        }
        radio.addActionListener(new ActionListener() {
            @Override public void actionPerformed(ActionEvent e) {
                initLinkBox(itemsPerPage, target+1);
            }
        });
        return radio;
    }
    private JRadioButton makePNRadioButton(final int itemsPerPage, final int target, String title) {
        JRadioButton radio = new JRadioButton(title);
        radio.setForeground(Color.BLUE);
        radio.setUI(ui);
        radio.addActionListener(new ActionListener() {
            @Override public void actionPerformed(ActionEvent e) {
                initLinkBox(itemsPerPage, target);
            }
        });
        return radio;
    }
    private RowFilter<TableModel,Integer> makeRowFilter(final int itemsPerPage, final int target) {
        return new RowFilter<TableModel,Integer>() {
            @Override public boolean include(Entry<? extends TableModel, ? extends Integer> entry) {
                int ei = entry.getIdentifier();
                int vi = table.convertRowIndexToView(ei);
                return (target*itemsPerPage<=vi && vi<target*itemsPerPage+itemsPerPage);
            }
        };
    }

    public static void main(String[] args) {
        EventQueue.invokeLater(new Runnable() {
            @Override public void run() {
                createAndShowGUI();
            }
        });
    }
    public static void createAndShowGUI() {
        try{
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
        }catch(Exception e) {
            e.printStackTrace();
        }
        JFrame frame = new JFrame();
        frame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
        frame.getContentPane().add(new TablePaginationTest().makeUI());
        frame.setSize(320, 240);
        frame.setLocationRelativeTo(null);
        frame.setVisible(true);
    }
}

class LinkViewRadioButtonUI extends BasicRadioButtonUI {
//     private final static LinkViewRadioButtonUI radioButtonUI = new LinkViewRadioButtonUI();
//     private boolean defaults_initialized = false;
//     public static ComponentUI createUI(JComponent b) {
//         return radioButtonUI;
//     }
//     protected void installDefaults(AbstractButton b){
//         super.installDefaults(b);
//         if(!defaults_initialized) {
//             icon = null; //UIManager.getIcon(getPropertyPrefix() + "icon");
//             defaults_initialized = true;
//         }
//     }
//     protected void uninstallDefaults(AbstractButton b){
//         super.uninstallDefaults(b);
//         defaults_initialized = false;
//     }
    @Override public Icon getDefaultIcon() {
        return null;
    }
    private static Dimension size = new Dimension();
    private static Rectangle viewRect = new Rectangle();
    private static Rectangle iconRect = new Rectangle();
    private static Rectangle textRect = new Rectangle();
    @Override public synchronized void paint(Graphics g, JComponent c) {
        AbstractButton b = (AbstractButton) c;
        ButtonModel model = b.getModel();
        Font f = c.getFont();
        g.setFont(f);
        FontMetrics fm = c.getFontMetrics(f);

        Insets i = c.getInsets();
        size = b.getSize(size);
        viewRect.x = i.left;
        viewRect.y = i.top;
        viewRect.width = size.width - (i.right + viewRect.x);
        viewRect.height = size.height - (i.bottom + viewRect.y);
        iconRect.x = iconRect.y = iconRect.width = iconRect.height = 0;
        textRect.x = textRect.y = textRect.width = textRect.height = 0;

        String text = SwingUtilities.layoutCompoundLabel(
            c, fm, b.getText(), null, //altIcon != null ? altIcon : getDefaultIcon(),
            b.getVerticalAlignment(), b.getHorizontalAlignment(),
            b.getVerticalTextPosition(), b.getHorizontalTextPosition(),
            viewRect, iconRect, textRect,
            0); //b.getText() == null ? 0 : b.getIconTextGap());

        if(c.isOpaque()) {
            g.setColor(b.getBackground());
            g.fillRect(0,0, size.width, size.height);
        }
        if(text==null) return;
//         // Changing Component State During Painting (an infinite repaint loop)
//         // pointed out by Peter
//         // -note: http://today.java.net/pub/a/today/2007/08/30/debugging-swing.html#changing-component-state-during-the-painting
//         //b.setForeground(Color.BLUE);
//         if(!model.isEnabled()) {
//             //b.setForeground(Color.GRAY);
//         }else if(model.isPressed() && model.isArmed() || model.isSelected()) {
//             //b.setForeground(Color.BLACK);
//         }else if(b.isRolloverEnabled() && model.isRollover()) {

        g.setColor(b.getForeground());
        if(!model.isSelected() && !model.isPressed() && !model.isArmed()
           && b.isRolloverEnabled() && model.isRollover()) {
            g.drawLine(viewRect.x,                viewRect.y+viewRect.height,
                       viewRect.x+viewRect.width, viewRect.y+viewRect.height);
        }
        View v = (View) c.getClientProperty(BasicHTML.propertyKey);
        if(v!=null) {
            v.paint(g, textRect);
        }else{
            paintText(g, b, textRect, text);
        }
    }
}
